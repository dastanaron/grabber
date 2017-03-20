#!/bin/bash


#Сначала добавляем все файлы в репоиторий
git add *.*
git add ./* -f


#Затем показываем пользователю его текущий коммит
git status

#Затем спрашиваем комитить или нет и какой комит
echo "Хотите ли вы сделать коммит? [д/н] | [y/n]";
read answer

if [[ "$answer" = "д" ]]
then

echo 'Напишите комментирий для коммита';

read comment

git commit -m "$comment"


fi #конец сравнения

if [[ "$answer" = "y" ]]
then

echo 'Напишите комментирий для коммита';

read comment

git commit -m "$comment"


fi #конец сравнения

#Далее можно комитить на гитхаб
#
#Я воспользовался генератором ключа 
#ssh-keygen -t rsa -b 4096 -C "your_email"
#Взял ключ из папки /home/user/.ssh/id_rsa и добавил в аккаунте на гитхабе
#Затем командой git remote add [сокращение] [адресс] - добавил в список своих удаленных репозиториев
#После синхронизировал через git fetch [сокращение]
#Использовал git pull [сокращение] [ветка]
#Теперь могу просто комитить туда

#echo "Хотите отправить комит на гитхаб? [д/н] | [y/n]";

#read answergithab

#Проверяем ответы на русском и английском и еси что комитим

#if [[ "$answergithab" = "д" ]]
#then

#git push conf master

#fi

#if [[ "$answergithab" = "y" ]]

#then

#git push conf master


#fi 

#git@github.com:dastanaron/configurator.git
