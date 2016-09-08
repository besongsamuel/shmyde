#!/bin/bash  
git add .
printf "What are the comments to this commit?  -> "
read COMMENT
git commit -m "$COMMENT"
git push origin master