#!/bin/bash

sed -i "s/#ZPUSH_HOST#/$ZPUSH_URL/" /var/www/html/config.php
sed -i "s/#ZIMBRA_HOST#/$ZIMBRA_HOST/" /var/www/html/config.php

apache2-foreground