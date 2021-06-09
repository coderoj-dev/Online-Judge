#include<bits/stdc++.h>
using namespace std;

int main(){
	
	system("gnome-terminal -- bash -c 'php artisan serve;bash'");
	system("gnome-terminal -- bash -c 'npm run watch;bash'");
	system("gnome-terminal -- bash -c 'watch -n 1 php artisan judge_process:cron1;bash'");
	system("gnome-terminal -- bash -c 'watch -n 1 php artisan judge:cron1;bash'");

}