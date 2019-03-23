# -*- coding: utf-8 -*-
# Common package
import json
import copy
import random
import requests
# Personal package


def hello_world(config):
    """
    尝试连接到lemon_tree服务器
    :return: 连接服务器结果
    """
    url = config['protocol'] + config['host'] + config['path']
    http_result = requests.get(url)
    if http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}
    http_data = json.loads(http_result.text)
    if http_data['status'] == 'success':
        return True, http_data
    else:
        return False, {'status': 'error', 'info': '数据传输异常'}
