# flux-ilias-rest-api

ILIAS Rest Api

*This is not a ILIAS plugin*

## Installation

### flux-ilias-rest-api

```dockerfile
COPY --from=docker-registry.fluxpublisher.ch/flux-ilias-rest-api:%tag% /flux-ilias-rest-api %web_root%/Customizing/global/flux-ilias-rest-api
```

or

```dockerfile
RUN (mkdir -p %web_root%/Customizing/global/flux-ilias-rest-api && cd %web_root%/Customizing/global/flux-ilias-rest-api && wget -O - https://github.com/flux-caps/flux-ilias-rest-api/releases/download/%tag%/flux-ilias-rest-api-%tag%-build.tar.gz | tar -xz --strip-components=1)
```

or

Download https://github.com/flux-caps/flux-ilias-rest-api/releases/download/%tag%/flux-ilias-rest-api-%tag%-build.tar.gz and extract it to `%web_root%/Customizing/global/flux-ilias-rest-api`

### nginx

#### In [flux-ilias-nginx-base](https://github.com/flux-caps/flux-ilias-nginx-base)

```dockerfile
RUN %web_root%/Customizing/global/flux-ilias-rest-api/bin/install-to-flux-ilias-nginx-base.sh
```

#### Other

```nginx
include %web_root%/Customizing/global/flux-ilias-rest-api/src/Adapter/Server/Config/nginx.conf
```

### apache

```apache
RewriteEngine On
Include %web_root%/Customizing/global/flux-ilias-rest-api/src/Adapter/Server/Config/apache.conf
```

### Helper Plugin

You need to install [flux-ilias-rest-helper-plugin](https://github.com/flux-caps/flux-ilias-rest-helper-plugin) and [flux-ilias-rest-object-helper-plugin](https://github.com/flux-caps/flux-ilias-rest-object-helper-plugin) too

### Config

Open `Administration` > `flux-ilias-rest-config` in ILIAS

### Example

[examples](examples)
