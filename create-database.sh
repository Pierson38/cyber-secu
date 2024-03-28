#!/bin/sh

sh vendor/bin/sail artisan migrate:fresh
sh vendor/bin/sail artisan db:seed
sh vendor/bin/sail artisan db:seed --class UserSeeder
sh vendor/bin/sail artisan db:seed --class ClientsSeeder

