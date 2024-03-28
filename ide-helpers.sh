#!/bin/sh

sh vendor/bin/sail artisan ide-helper:generate
sh vendor/bin/sail artisan ide-helper:models -W
sh vendor/bin/sail artisan ide-helper:meta

