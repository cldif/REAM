#!/bin/bash

port=8001
php -S localhost:$port -t ./adminer & xdg-open localhost:$port
