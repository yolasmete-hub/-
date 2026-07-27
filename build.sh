#!/usr/bin/env bash
# Solidarity Joomla şablonunu kurulabilir zip olarak paketler.
set -euo pipefail

cd "$(dirname "$0")"

VERSION="$(sed -n 's:.*<version>\(.*\)</version>.*:\1:p' templates/solidarity/templateDetails.xml | head -1)"
OUT="dist/tpl_solidarity_${VERSION}.zip"

mkdir -p dist
rm -f "$OUT"

if command -v zip >/dev/null 2>&1; then
    (cd templates/solidarity && zip -r "../../$OUT" . -x '*.DS_Store')
else
    python3 - "$OUT" <<'PY'
import os, sys, zipfile

out = sys.argv[1]
root = "templates/solidarity"
with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as z:
    for base, _, files in os.walk(root):
        for name in files:
            if name == ".DS_Store":
                continue
            path = os.path.join(base, name)
            z.write(path, os.path.relpath(path, root))
PY
fi

echo "Paket hazır: $OUT"
