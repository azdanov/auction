# Auction

An auction app made in PHP and JavaScript with Slim and React

## Development Setup

- Start docker compose `make up`
- Stop docker compose `make down`

## Production Setup

Run for **first** setup only:

1. Run scripts from [provisioning/README.md](./provisioning/README.md) to setup vps with ansible.
2. Login to docker `docker login`
3. Build first version `REGISTRY=docker_username IMAGE_TAG=master-1 make build`
4. Push images `REGISTRY=docker_username IMAGE_TAG=master-1 make push`
5. Deploy to vps `HOST=deploy@0.0.0.0 PORT=22 REGISTRY=azdanov IMAGE_TAG=master-1 BUILD_NUMBER=1 make deploy`

Troubleshooting:
- Give missing permissions. `sudo chmod +x /usr/local/bin/docker-compose`