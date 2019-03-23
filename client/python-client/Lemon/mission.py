# -*- coding: utf-8 -*-
# Common package
import json
import random
import requests
# Personal package
import Lemon.static_func as f


def init(config):
    """
    初始化服务器状态（该操作将删除历史数据！！！）
    :return: 初始化结果
    """
    v_code = random.randint(1000, 9999)
    f.print_y('该操作将删除服务器中的历史数据，请在下方输入数字验证码-={}=-以确认。'.format(v_code))
    i_code = input('请键入：')
    if str(i_code) != str(v_code):
        f.print_r('验证码键入错误，退出初始化程序')
        return False, {'status': 'error', 'info': '验证码输入错误'}
    else:
        f.print_g('正在进行初始化操作，请勿打断程序...')

    url = config['protocol'] + config['host'] + config['path']['mission_init']
    header = {'X-Auth-Token': config['token']}
    http_result = requests.get(url, header=header)
    if http_result.status_code == 401:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def post(config, method, host, path, header='', param='', data='', target=1):
    """
    上传一条Mission任务
    :param config: 配置文件
    :param method:
    :param host:
    :param path:
    :param header:
    :param param:
    :param data:
    :param target:
    :return: 上传结果
    """
    http_data = {
        'method': method,
        'host': host,
        'path': path,
        'header': dict(header),
        'param': dict(param),
        'data': str(data),
        'target': target
    }
    http_url = config['protocol'] + config['host'] + config['path']['mission']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.post(http_url, header=http_header, data=http_data)

    if http_result.status_code == 403:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def get(config, mid):
    """
    查询一条Mission任务
    :param config: 配置文件
    :param mid: 任务ID
    :return: 任务信息
    """
    http_param = {'mid': mid}
    http_url = config['protocol'] + config['host'] + config['path']['mission']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.post(http_url, header=http_header, params=http_param)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def put(config, method, host, path, header='', param='', data='', target=1, download=0, upload=0, success=0, error=0):
    """
    修改一条Mission任务
    :param config:
    :param method:
    :param host:
    :param path:
    :param header:
    :param param:
    :param data:
    :param target:
    :param download:
    :param upload:
    :param success:
    :param error:
    :return: 修改结果
    """
    http_data = {
        'method': method,
        'host': host,
        'path': path,
        'header': dict(header),
        'param': dict(param),
        'data': str(data),
        'target': target,
        'download': download,
        'upload': upload,
        'success': success,
        'error': error
    }
    http_url = config['protocol'] + config['host'] + config['path']['mission']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.put(http_url, header=http_header, data=http_data)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data


def delete(config, mid):
    """
    查询一条Mission任务
    :param config: 配置文件
    :param mid: 任务ID
    :return: 任务信息
    """
    http_param = {'mid': mid}
    http_url = config['protocol'] + config['host'] + config['path']['mission']
    http_header = {'X-Auth-Token': config['token']}
    http_result = requests.get(http_url, header=http_header, params=http_param)

    if http_result.status_code in [403, 406]:
        http_data = json.loads(http_result.text)
        return False, http_data
    elif http_result.status_code != 200:
        return False, {'status': 'error', 'info': '服务器连接失败'}

    http_data = json.loads(http_result.text)
    return True, http_data
