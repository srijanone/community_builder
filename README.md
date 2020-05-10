This Module enables users to create communities/forums.

- Clone the repository.   
``git clone git@github.com:srijanone/community-platform-builder.git community_builder_dev``
- go to cloned directory ``community_builder_dev``

## Branching Strategy
#### Note: Make sure you have set git config(email, user, etc) matching to drupal.org as then only you will be able to receive credits.

- Create branch dev.
- Format:
``git checkout -b [ProjectKey-TicketNumber]/[short description]``
- Example:
``git checkout -b CB-001/create-new-repo``
- Commit should also contain the ticket number and short message
- Format:
``git commit -m "[ProjectKey-TicketNumber]:[message]"``
- Example:
``git commit -m "CB-001:Created repo and added ERADME."``
 - Rebase with dev
 ``git pull origin --rebase dev``
 - git push
