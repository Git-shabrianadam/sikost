standar workflow in git :
1. Start coding
2. Finish a small feature
3. git add .
4. git commit -m "what you implemented"
5. Continue coding
6. repeat step 2-4

start sequence : cd to path C:\xampp\htdocs\sikost, then git status
expected nothing to commit, working tree clean

development sequence : git add ., git commit -m "what you implemented"

end sequence : git status, git add .
then git commit -m "WIP : what you want to continue tomorrow"

repository source, github : 
PS C:\xampp\htdocs\sikost> git remote -v
origin  https://github.com/Git-shabrianadam/sikost.git (fetch)
origin  https://github.com/Git-shabrianadam/sikost.git (push)