<?php

namespace App\Services\Contest;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;

class ContestService
{
    public function bannerUpdate(Contest $contest, $data)
    {
        if ($data->hasfile('banner')) {
            if ($contest->banner != null) {
                $baseName = basename($contest->banner);
                if (file_exists(public_path($contest->bannerPath) . $baseName)) {
                    unlink(public_path($contest->bannerPath) . $baseName);
                }
            }
            $banner   = $data->banner;
            $fileName = hash('sha256', $contest->slug . '-' . Str::random(20) . "-" . time()) . "." . $banner->extension();
            $banner->move(public_path($contest->bannerPath), $fileName);
            $contest->banner = $fileName;
        }
        return $contest;
    }

    public function updateContest(Contest $contest, $data)
    {
        // dd(isset($data->publish));
        $contest                           = $this->bannerUpdate($contest, $data);
        $contest->name                     = $data->name;
        $contest->format                   = $data->format;
        $contest->start                    = $data->start;
        $contest->duration                 = $data->duration;
        $contest->publish                  = isset($data->publish);
        $contest->description              = $data->description;
        $contest->visibility               = $data->visibility;
        $contest->password                 = $data->password == null ? null : hash('sha256', $data->password);
        $contest->registration_auto_accept = isset($data->registration_auto_accept);
        $contest->participate_main_name = $data->participate_main_name;
        $contest->participate_sub_name = $data->participate_sub_name;
        $contest->save();
    }

    public function addProblem(Contest $contest, $slug)
    {
        $problem = Problem::where(['slug' => $slug])->firstOrFail();
        // return $problem->slug;
        $contest->problems()->sync([$problem->id => ['user_id' => auth()->user()->id]]);
        return "Problem Added Successfully";
    }
    public function removeProblem(Contest $contest, $problem_id)
    {
        $contest->problems()->detach($problem_id);
        return "Problem Removed";
    }

    public function generateTempUser(Contest $contest, $data)
    {
        $handlePrefix   = $data['handlePrefix'];
        $passwordLength = $data['passwordLength'];
        $csvData        = $data['csvData'];

        //get all collumns in csv file
        $csvCollumns = $csvData[0];
        unset($csvData[0]);

        $userData = [];
        foreach ($csvData as $key => $user) {
            $tmp = [];
            foreach ($csvCollumns as $key => $value) {
                $tmp[$value] = $user[$key];
            }
            array_push($userData, $tmp);
        }

        $handleNo = User::whereRaw("handle RLIKE '^{$handlePrefix}'")->count();

        //store temp user data
        foreach ($userData as $key => $value) {

            while (1) {

                $handleNo++;
                $userHandle = $handlePrefix . ($handleNo < 10 ? "0" : "") . $handleNo;
                if (User::where(['handle' => $userHandle])->exists()) {
                    continue;
                }

                $emailRand    = Str::random(10);
                $userEmail    = "tempmail-$userHandle-$emailRand@tmp.coderoj.com";
                $userPassword = Str::random($passwordLength);

                $user = User::create([
                    'handle'   => $userHandle,
                    'name'     => isset($value['name']) ? $value['name'] : $userHandle,
                    'password' => $userPassword,
                    'email'    => $userEmail,
                    'type'     => 'temp_member',
                ]);

                $contest->registrations()->attach($user->id, [
                    'registration_data'        => json_encode($value),
                    'is_registration_accepted' => 1,
                    'is_temp_user'             => 1,
                    'temp_user_password'       => $userPassword,
                ]);

                break;
            }
        }

        //update user data field

        $userDataField = $contest->user_data_field;

        $registrationData = collect($userDataField['registration']);
        $registrationData = $registrationData->merge($csvCollumns);
        $registrationData = $registrationData->unique();
        $registrationData = $registrationData->toArray();

        $userDataField['registration'] = $registrationData;

        $contest->update([
            'user_data_field' => json_encode($userDataField),
        ]);

        $totalUser = count($userData);
        return response()->json([
            'message' => "{$totalUser} Temporary User Successfully Created.",
        ]);
    }
}
