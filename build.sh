#!/bin/bash

# Mosaic Build Script
# Builds the design system library into a distributable zip

set -e  # Exit on error

echo "=============================================="
echo "Mosaic Build"
echo "=============================================="
echo ""

# Colors for output
GREEN='\033[38;2;39;201;63m'
YELLOW='\033[38;2;222;184;65m'
BLUE='\033[38;2;59;130;246m'
GRAY='\033[38;2;136;136;136m'
NC='\033[0m' # No Color

# Get script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

# Build directory
BUILD_DIR="$SCRIPT_DIR/build"
WORK_DIR="$BUILD_DIR/work"

# Clean previous build
echo -e "${BLUE}Cleaning previous build...${NC}"
rm -rf "$BUILD_DIR"
mkdir -p "$WORK_DIR"

# Get version using vermouth
echo -e "${BLUE}Reading version from git tags...${NC}"
VERSION=$(vermouth 2>/dev/null || curl -sfL https://raw.githubusercontent.com/abrayall/vermouth/refs/heads/main/vermouth.sh | sh -)

echo -e "${GREEN}Building version: ${VERSION}${NC}"
echo ""

# Package library
echo -e "${YELLOW}=== Packaging Mosaic ===${NC}"
echo ""

ZIP_NAME="mosaic-${VERSION}.zip"
echo -e "${BLUE}Creating ${ZIP_NAME}...${NC}"

# Create mosaic/ subfolder structure in work directory
mkdir -p "$WORK_DIR/mosaic"

# Copy assets
echo -e "${GRAY}  Copying assets...${NC}"
cp -r "$SCRIPT_DIR/assets" "$WORK_DIR/mosaic/"

# Copy includes
echo -e "${GRAY}  Copying includes...${NC}"
cp -r "$SCRIPT_DIR/includes" "$WORK_DIR/mosaic/"

# Copy docs
echo -e "${GRAY}  Copying docs...${NC}"
cp -r "$SCRIPT_DIR/docs" "$WORK_DIR/mosaic/"

# Copy root files
echo -e "${GRAY}  Copying root files...${NC}"
cp "$SCRIPT_DIR/README.md" "$WORK_DIR/mosaic/"
cp "$SCRIPT_DIR/mosaic.properties" "$WORK_DIR/mosaic/"

# Write version file
echo "version=${VERSION}" > "$WORK_DIR/mosaic/version.properties"

# Create zip
cd "$WORK_DIR"
zip -r "$BUILD_DIR/$ZIP_NAME" mosaic -x "*.DS_Store" -x "*/.git/*" -x "*/.*"
cd "$SCRIPT_DIR"

echo -e "${GREEN}✓ Created: ${ZIP_NAME}${NC}"
echo ""

# Summary
echo "=============================================="
echo -e "${GREEN}Build Complete!${NC}"
echo "=============================================="
echo ""
echo "Artifacts created in build/:"
ls -1 "$BUILD_DIR"/*.zip
echo ""
