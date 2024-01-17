#!/bin/bash

# Check if correct number of arguments is provided
if [ "$#" -ne 3 ]; then
    echo "Usage: $0 textimg.exe input.txt output.png"
    exit 1
fi

# Read content from the specified file
value=$(<"${2}")

# Run textimg.exe with the value and save the output to the specified file
./"${1}" "${value}" -o "${3}"