# X (Official) MCP Server

This repository is configured to use the official **X (Twitter) MCP server**
via [`@xdevplatform/xurl`](https://www.npmjs.com/package/@xdevplatform/xurl).
The server is declared in [`.mcp.json`](./.mcp.json) so any MCP-aware client
(e.g. Claude Code) in this directory can connect to it.

## What it does

`xurl` exposes the X API (`https://api.x.com/mcp`) as MCP tools, letting an
agent read and post to X on your behalf using OAuth 2.0.

## Setup

1. **Create an X app** at <https://developer.x.com> and grab its OAuth 2.0
   **Client ID** and **Client Secret**.

2. **Provide the credentials as environment variables.** The `.mcp.json`
   config reads them from the environment so secrets stay out of git:

   ```bash
   cp .env.example .env
   # then edit .env and fill in X_CLIENT_ID / X_CLIENT_SECRET
   ```

   Export them in your shell (or use a tool like `direnv` / `dotenv`):

   ```bash
   export X_CLIENT_ID="..."
   export X_CLIENT_SECRET="..."
   ```

3. **Start your MCP client** in this directory. It will launch the server with
   `npx -y @xdevplatform/xurl mcp https://api.x.com/mcp`.

## Security

- **Never commit real credentials.** `.env` is listed in `.gitignore`; only
  `.env.example` (with placeholders) is tracked.
- `.mcp.json` references `${X_CLIENT_ID}` / `${X_CLIENT_SECRET}` rather than
  embedding literal values, so the client secret is never stored in the repo
  or its history.
- If a client secret is ever exposed, **rotate it** in the X developer portal.

## Files

| File             | Purpose                                            |
| ---------------- | -------------------------------------------------- |
| `.mcp.json`      | MCP server definition (X official server).         |
| `.env.example`   | Template for the required OAuth env vars.          |
| `.gitignore`     | Keeps `.env` and local secrets out of git.         |
