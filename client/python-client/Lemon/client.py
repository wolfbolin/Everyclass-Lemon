# -*- coding: utf-8 -*-
# Common package

# Personal package
import Lemon.config as config
import Lemon.mission as mission
import Lemon.statistic as statistic


class Client:
    config = config.default_config()

    def __init__(self, token):
        self.config['token'] = token

    def hello_world(self):
        return statistic.hello_world(self.config)

    #################################################################################

    def mission_init(self):
        return mission.init(self.config)

    def mission_post(self, method, host, path, header='', param='', data='', target=1):
        return mission.post(self.config, method, host, path, header, param, data, target)

    def mission_get(self, mid):
        return mission.get(self.config, mid)

    def mission_put(self, method, host, path, header='', param='', data='',
                    target=1, download=0, upload=0, success=0, error=0):
        return mission.put(self.config, method, host, path, header, param, data,
                           target, download, upload, success, error)

    def mission_delete(self, mid):
        return mission.delete(self.config, mid)

    #################################################################################

    def cookie_post(self, cookie, time=0):
        return mission.post(self.config, cookie, time)

    def cookie_get(self, cid):
        return mission.get(self.config, cid)

    def cookie_put(self, cookie, time=0, download=0, upload=0, success=0, error=0):
        return mission.put(self.config, cookie, time, download, upload, success, error)

    def cookie_delete(self, cid):
        return mission, delattr(self.config, cid)
