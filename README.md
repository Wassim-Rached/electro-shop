# Electro shop

## Getting Started

To get started with the project, follow these steps:

1. **Clone the Dev Repository**

Make sure you have Git installed on your machine. Then, clone the dev repository:

[ **if it doesn't work just use the IDE to clone** ]

```bash
	git clone -b dev https://github.com/Wassim-Rached/mini-shop.git
```

2. **Access the Website**

Once the Docker containers are up and running, you can access the website at:

- Website: http://localhost:1001
- PHPMyAdmin: http://localhost:1002

3. **Check for Problems**

To check if the project has any problems, you can visit the following endpoints:

- Health Check: http://localhost:1001/health-check
- Database Health Check: http://localhost:1001/health-check/db

4. **Execute Migrations** (If Needed)

If there are any problems with the database

```bash
	docker exec -it mini-shop_fpm bash
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate
```

5. **Commit Changes to Dev Branch**

Ensure that all your code changes are committed with meaningful messages to the dev branch:

```bash
	git add .
	git commit -m "commit message"
	git push origin dev
```

## The Branch Workflow

This Repo Follows this branch workflow for code changes:

- **Dev**: All code changes should be pushed to the dev branch.
- **Experimental** Branch: Code changes from the dev branch will be reviewed and pushed to the experimental branch for further testing.

- **Production**: Code changes from the experimental branch will be pushed to the production branch after thorough testing.
