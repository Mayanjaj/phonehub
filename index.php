<?php
// You opened the project folder. The website itself lives in the public/ folder, so forward there.
// (Best practice is to point Apache at public/ with a virtual host: see docs/deployment_guide.md, Part A.)
header('Location: public/');
exit;
