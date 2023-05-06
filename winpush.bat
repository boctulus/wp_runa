@echo off

set count=0
for %%x in (%*) do set /a count+=1

if %count%==0 (
  echo Please provide a commit message.
  exit /b 1
)

git add *
git commit -m "%1"
git push
