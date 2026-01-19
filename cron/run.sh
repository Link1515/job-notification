#!/bin/bash

cd /home/terry/projects/job-notification

docker compose run --rm crawler >> /var/log/projects/job-notification-crawler.log 2>&1

# TODO 設定 logrotate
