up:
	symfony server:stop
	docker compose up -d
	symfony server:start -d --no-tls
	symfony console messenger:setup-transports
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async
	symfony server:log

down:
	docker compose down
	symfony server:stop
