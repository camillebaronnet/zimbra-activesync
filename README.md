# ActiveSync for Zimbra with Z-push and Autodiscover

## Get started

It's very simple, first, get it :

```bash
docker pull camillebaronnet/zimbra-activesync
```

And run it :

```bash
docker run -d \
	-p 80:80 \
	-e ZIMBRA_HOST=myemaildomain.tld \
	-e ZPUSH_URL=myzpushdomain.tld \
	--name zimbra-activesync
	camillebaronnet/zimbra-activesync
```

## From Github

Clone from the Github project, build it and launch it

```bash
git pull https://github.com/camillebaronnet/zimbra-activesync.git
cd zimbra-activesync
docker build -t zimbra-activesync .
docker run [...] zimbra-activesync
```
