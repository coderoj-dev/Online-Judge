gnome-terminal -- bash -c "php artisan serve;bash"
gnome-terminal -- bash -c "npm run watch;bash"
gnome-terminal -- bash -c "watch -n 1 php artisan judge_process:cron1;bash"
gnome-terminal -- bash -c "watch -n 1 php artisan judge:cron1;bash"
