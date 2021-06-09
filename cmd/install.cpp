#include<bits/stdc++.h>
using namespace std;

int main(){
	system("composer install");
	system("npm install");
	system("cp .env.example .env");
	system("php artisan key:generate");
	system("php artisan migrate");
	system("php artisan db:seed");
}