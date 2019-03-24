#!/bin/bash
# 删除已有的镜像
docker stop lemon
docker rm lemon
docker rmi lemon_tree:latest
# 制作docker镜像
docker build --tag lemon_tree:latest .
# 启动docker实例
docker run -itd --name lemon -v "$PWD":/www -p 25600:80 lemon_tree
docker ps -a
docker logs lemon -f