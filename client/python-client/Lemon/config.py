# -*- coding: utf-8 -*-


def default_config():
    return {
        'protocol': 'http://',
        'host': 'localhost:8080',
        'token': '',
        'path': {
            'hello_world': '/hello_world',
            'mission_init': '/mission/init',
            'mission': '/mission',
            'cookie': '/cookie'
        }
    }
