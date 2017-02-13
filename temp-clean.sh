#!/bin/bash

rm -rf app/runtime/mail/*
echo "runtime/mail/* files deleted.";

rm -rf app/www/assets/*
echo "www/assets/* files deleted.";

rm -rf app/runtime/debug/mail/*.eml
echo "runtime/debug/mail/*.eml files deleted.";

> app/runtime/logs/app.log
echo "app.log cleaned.";

