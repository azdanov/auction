# Provisioning with Ansible

Works on Ubuntu 20.04 with 1 GB RAM. Another option is to use Vagrant to test locally.

```shell
mv hosts.yml.dist hosts.yml # And modify user/ip as needed
```

- `ansible all -m ping -i hosts.yml` - to check if ansible can reach specified hosts
- `make site` - to set up auction app on vps (docker, certbot, certificates, user)
- `make authorize` - to set up user's ssh key
- `make docker-login` - to log in to docker registry (if you can't connect to socket then run from ssh `sudo chmod 666 /var/run/docker.sock`)
- `make renew-certificates` - to renew certbot certificates