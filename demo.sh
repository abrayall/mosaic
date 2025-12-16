#!/bin/bash

# Mosaic Demo Deploy Script
# Builds the library and deploys the demo plugin to WordPress

set -e  # Exit on error

# Colors for output
GREEN='\033[38;2;39;201;63m'
YELLOW='\033[38;2;222;184;65m'
NC='\033[0m' # No Color

# Get script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "=============================================="
echo "Mosaic Demo Deploy"
echo "=============================================="
echo ""

# Step 1: Build the library
echo -e "${YELLOW}=== Building Mosaic Library ===${NC}"
echo ""
"$SCRIPT_DIR/build.sh"
echo ""

# Step 2: Deploy the demo plugin
echo -e "${YELLOW}=== Deploying Demo Plugin ===${NC}"
echo ""
cd "$SCRIPT_DIR/demo"
wordsmith deploy
echo ""

echo "=============================================="
echo -e "${GREEN}Demo Deploy Complete!${NC}"
echo "=============================================="
echo ""
echo "The Mosaic demo is now available in WordPress admin under 'Mosaic'"
echo ""
