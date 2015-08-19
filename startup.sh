#!/bin/bash

sed -i "s/#ZPUSH_HOST#/$ZPUSH_URL/" /home/www/public/config.php
sed -i "s/#ZIMBRA_HOST#/$ZIMBRA_HOST/" /home/www/public/config.php

/usr/sbin/apache2 -D FOREGROUND