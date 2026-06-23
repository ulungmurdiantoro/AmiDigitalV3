<#
  Push lokal -> deploy server dalam SATU perintah (versi PowerShell, utk Windows).

  Pakai:
    .\push-deploy.ps1            # git push, lalu jalankan deploy.sh di server
    .\push-deploy.ps1 -Seed      # + db:seed di server (data master / awal)

  Konfigurasi: salin .deploy.env.example -> .deploy.env lalu isi
  (SSH_HOST, SSH_USER, SSH_PORT, REMOTE_DIR, BRANCH).
#>

[CmdletBinding()]
param(
    [switch]$Seed
)

$ErrorActionPreference = 'Stop'
Set-Location -Path $PSScriptRoot

# --- Muat konfigurasi dari .deploy.env (KEY=value, abaikan komentar) ---
$cfg = @{}
if (Test-Path '.deploy.env') {
    foreach ($line in Get-Content '.deploy.env') {
        $t = $line.Trim()
        if (-not $t -or $t.StartsWith('#') -or -not $t.Contains('=')) { continue }
        $parts = $t -split '=', 2
        $key = $parts[0].Trim()
        $val = ($parts[1] -replace '\s+#.*$', '').Trim()   # buang komentar inline
        $cfg[$key] = $val
    }
}

function Get-Cfg($name, $default) {
    $envVal = [Environment]::GetEnvironmentVariable($name)   # env var menang
    if ($envVal) { return $envVal }
    if ($cfg.ContainsKey($name) -and $cfg[$name]) { return $cfg[$name] }
    return $default
}

$SSH_HOST   = Get-Cfg 'SSH_HOST'   ''
$SSH_USER   = Get-Cfg 'SSH_USER'   ''
$SSH_PORT   = Get-Cfg 'SSH_PORT'   '22'
$REMOTE_DIR = Get-Cfg 'REMOTE_DIR' '/var/www/amidigital.sistemedu.com'
$BRANCH     = Get-Cfg 'BRANCH'     'master'

if (-not $SSH_HOST) { throw "SSH_HOST belum diisi. Salin .deploy.env.example -> .deploy.env lalu isi." }
if (-not $SSH_USER) { throw "SSH_USER belum diisi (edit .deploy.env)." }

$deployArgs = if ($Seed) { '--seed' } else { '' }

function Log($msg) { Write-Host "`n==> $msg" -ForegroundColor Cyan }

Log "Push commit lokal ke origin/$BRANCH"
git push origin $BRANCH
if ($LASTEXITCODE -ne 0) { throw "git push gagal (exit $LASTEXITCODE)." }

Log "Deploy di server: $SSH_USER@$SSH_HOST`:$REMOTE_DIR  (deploy.sh $deployArgs)"
$remoteCmd = "cd '$REMOTE_DIR' && bash deploy.sh $deployArgs"
ssh -p $SSH_PORT "$SSH_USER@$SSH_HOST" $remoteCmd
if ($LASTEXITCODE -ne 0) { throw "deploy di server gagal (exit $LASTEXITCODE)." }

Log "Selesai: push + deploy beres."
