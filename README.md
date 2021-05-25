# MealPlanner

an extension for my null hub that does meal planning and recipes. i'm going to get this setup to do what i need for the micro display project. i'll eventually want to pull the full meal planner out of my hub project so it can be an open source project.

## clone repo

```bash
cd /var/www/html/extensions
```

```bash
git clone https://github.com/sophiathekitty/MealPlanner.git
```

### setup cron job

```bash
sudo crontab -e
```

```Apache config
3 * * * * sh /var/www/html/extensions/MealPlanner/gitpull.sh
```
