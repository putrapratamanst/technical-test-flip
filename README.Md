Please follow this step
1. Create Folder Logs. Run in terminal:  ***mkdir logs*** 
2. Get Address Current Project Folder. Run in terminal: ***pwd***
3. Copy result address folder. Give permission folder logs. Run in terminal: ***sudo chmod -R 777 {pathprojectlogs}***
4. Please set DB_USERNAME and DB_PASSWORD in file .env
5. Back to path project
6. Go to console folder: Run in terminal : ***cd console/migrations*** 
7. Do migratin, Run in terminal : ***php migration.php***
8. Back to path project
9. Run in terminal : ***sudo su***
10. Run cron: * * * * * /usr/bin/php /home/putra/Documents/JOJONOMIC/CODE/technical-test-flip/scheduler/UpdateStatus.php >> /var/log/cron.log 2>&1
11. Run in terminal:  ***cd web***
12. Please type in terminal : ***php -S localhost:8088***, and enter
13. Open Browser and type : ***localhost:8088***
