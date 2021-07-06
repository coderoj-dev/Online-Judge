<?php

namespace App\Services\Contest;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Str;

class ContestService
{
    protected $contest;

    public function __construct()
    {
        if (isset(request()->contest_id)) {
            $this->contest = Contest::where(['id' => request()->contest_id])->firstOrFail();
        }
    }
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
        $contest->format                   = $data->format;
        $contest->start                    = $data->start;
        $contest->duration                 = $data->duration;
        $contest->publish                  = isset($data->publish);
        $contest->description              = $data->description;
        $contest->visibility               = $data->visibility;
        $contest->password                 = $data->password;
        $contest->registration_auto_accept = isset($data->registration_auto_accept);
        $contest->participate_main_name    = $data->participate_main_name;
        $contest->participate_sub_name     = $data->participate_sub_name;

        if (isset($data->name)) {
            if (\Str::slug($contest->name) != \Str::slug($data->name)) {
                $contest->slug = $this->createSlug($data->name);
            }
        }

        $contest->name = $data->name;

        $contest->save();

        $contest->registrationCacheData()->save();
        $contest->rankList()->save();
    }

    public function createSlug($contestName)
    {
        $slug = \Str::slug($contestName);

        if ($slug == "") {
            $slug = "contest";
        }

        // check to see if any other slugs exist that are the same & count them
        $count = Contest::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        while (1) {
            $tmpSlug = $count ? "{$slug}-{$count}" : $slug;
            if (Contest::where('slug', '=', $tmpSlug)->exists()) {
                $count++;
                continue;
            }
            break;
        }
        // if other slugs exist that are the same, append the count to the slug
        $slug = $count ? "{$slug}-{$count}" : $slug;

        return $slug;
    }

    public function addProblem(Contest $contest, $slug)
    {

        $problem = Problem::where(['slug' => $slug])->firstOrFail();
        $contest->problems()->attach($problem->id, [
            'user_id' => auth()->user()->id,
            'serial'  => Carbon::now()->timestamp,
        ]);
        $contest->rankList()->save();
        return "Problem Added Successfully";
    }
    public function removeProblem(Contest $contest, $problem_id)
    {
        $contest->problems()->detach($problem_id);
        $contest->rankList()->save();
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

        $contest->registrationCacheData()->save();
        $contest->rankList()->save();

        $totalUser = count($userData);
        return response()->json([
            'message' => "{$totalUser} Temporary User Successfully Created.",
        ]);
    }

    public function getDatatableColumn()
    {

        $tableColumn   = [];
        $userDataField = $this->contest->user_data_field;

        $defaultColumn = ['handle', 'main_name', 'sub_name', 'Registration Time', 'Registration Status', 'Is Temp User', 'Temp User Password', 'name', 'email'];

        array_push($tableColumn, ['data' => 'action', 'orderable' => false]);

        foreach ($defaultColumn as $key => $value) {
            array_push($tableColumn, ['data' => $value]);
        }

        foreach ($userDataField['registration'] as $key => $value) {
            if ((array_search($value, $defaultColumn)) !== false) {
                continue;
            }

            array_push($tableColumn, ['data' => $value]);
        }
        return $tableColumn;
    }

    public function getDatatableData(Contest $contest)
    {
        $users         = $this->contest->registrationCacheData()->get();
        $userDataField = $contest->user_data_field;

        $datas = [];

        //'registration_time','temp_user','temp_user_password','registration_status'
        $serial = 0;
        foreach ($users as $key => $user) {
            $data = (array) $user;

            $data['serial']              = $serial++;
            $data['action']              = "<input type='checkbox' name='registrations[]' value='{$user->id}'>";
            $data['handle']              = "<a href=''> {$user->handle}</a>";
            $data['Registration Time']   = "<font title='{$user->registration_time->format('d M Y g:i A')}'>{$user->registration_time->diffForhumans()}</font>";
            $data['Is Temp User']        = $user->is_temp_user ? "<i class='fa fa-check'></i>" : "";
            $data['Temp User Password']  = $user->temp_user_password;
            $data['Registration Status'] = $user->is_registration_accepted ? "<span class='label label-success'><i class='fa fa-check'></i> Accepted</span>" : "<span class='label label-warning'><i class='fa fa-clock'></i> Pending</span>";

            array_push($datas, $data);
        }

        $tmpData = $datas;

        $datas = collect($datas);
        //datatable not working html in some row so we need to work html
        $data = datatables($datas)->toArray();

        $datatableData = $data['data'];

        foreach ($datatableData as $key => $value) {
            $datatableData[$key] = $tmpData[$value['serial']];
        }

        $data['data'] = $datatableData;

        return json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function createMailTemplate(Contest $contest)
    {
        return view("pages.administration.contest.mail.welcome", ['contest' => $contest])->render();
    }

    public function createName(User $user, $data, $name)
    {
        $data['handle'] = $user->handle;
        foreach ($data as $key => $value) {
            $name = str_replace("@$key@", $value, $name);
        }
        return $name;
    }

}
