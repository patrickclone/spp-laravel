<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	@vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
	@inertiaHead
</head>
<body>
	@inertia
</body>
</html>