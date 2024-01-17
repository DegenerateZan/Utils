

# Read content from the specified input file
$value = Get-Content -Raw -Path $args[1]

# Run textimg.exe with the value and save the output to the specified file
& $args[0] $value -o $args[2]