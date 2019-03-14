# -*- coding: utf-8 -*-
# Common package
import json
import random
import requests
# Personal package
import Lemon.static_func as f


def post(config, cookie, time=0):
    """
    上传一条Cookie任务
    :param config: 配置文件
    :param cookie:
    :param time:
    :return: 上传结果
    """
    http_data = {
        'cookie': cookie,
        'time': time
    }
    http_url = config['protocol'] + config['host'] + config['path']['cookie']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.post(http_url, header=http_header, data=http_data)

    if http_result.status_code in [403]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def get(config, cid):
    """
    查询一条Cookie任务
    :param config: 配置文件
    :param cid: 任务ID
    :return: 任务信息
    """
    http_param = {'cid': cid}
    http_url = config['protocol'] + config['host'] + config['path']['cookie']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.post(http_url, header=http_header, params=http_param)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def put(config, cookie, time=0, download=0, upload=0, success=0, error=0):
    """
    修改一条Cookie任务
    :param config:
    :param cookie:
    :param time:
    :param download:
    :param upload:
    :param success:
    :param error:
    :return: 修改结果
    """
    http_data = {
        'cookie': cookie,
        'time': time,
        'download': download,
        'upload': upload,
        'success': success,
        'error': error
    }
    http_url = config['protocol'] + config['host'] + config['path']['cookie']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.put(http_url, header=http_header, data=http_data)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def delete(config, cid):
    """
    查询一条Cookie任务
    :param config: 配置文件
    :param cid: 任务ID
    :return: 任务信息
    """
    http_param = {'cid': cid}
    http_url = config['protocol'] + config['host'] + config['path']['cookie']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.get(http_url, header=http_header, params=http_param)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data
