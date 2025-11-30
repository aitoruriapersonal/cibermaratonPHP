#!/usr/bin/env bash

# Script: fetch_10min.sh
# Llama a la URL cada ejecución y añade la respuesta al fichero diario (append).
# Uso: ejecutarse desde cron como el usuario configurado.

URL="http://www.ajedrez.deporte-universitario.com/backend/operativa/botBuscadorDatosUnitarioHTML.php"
#LOG_DIR="/deporteuniversitario/ajedrez/backend/linuxLog"
LOG_DIR="/var/log/cibermaratonSh"
DATE="$(date +%Y-%m-%d)"
OUT_FILE="$LOG_DIR/$DATE.log"
TMP_FILE="$(mktemp)"

# Asegurar directorio de logs
mkdir -p "$LOG_DIR"
chmod 750 "$LOG_DIR" 2>/dev/null || true

# Ruta absoluta de curl para evitar problemas con cron
CURL_BIN="/usr/bin/curl"

# Petición HTTP a fichero temporal
"$CURL_BIN" --fail -sS --retry 2 --max-time 30 "$URL" -o "$TMP_FILE"
CURL_EXIT=$?

if [ $CURL_EXIT -eq 0 ]; then
  # Añadir separador y timestamp antes del contenido para cada ejecución (append)
  {
    printf "\n\n# --- Fetched at %s ---\n" "$(date -u +'%Y-%m-%dT%H:%M:%SZ')"
    cat "$TMP_FILE"
  } >> "$OUT_FILE"
  rm -f "$TMP_FILE"
  exit 0
else
  # En caso de fallo, registra el error en fetch_errors.log
  echo "[$(date -u +'%Y-%m-%dT%H:%M:%SZ')] ERROR: curl exit code $CURL_EXIT when fetching $URL" >> "$LOG_DIR/fetch_errors.log"
  rm -f "$TMP_FILE"
  exit $CURL_EXIT
fi