# Database

This file holds the description of the database schema.

## Releases

### Table: wp_lchb_releases
 - ID: int
 - product_id: int
 - version: string
 - changelog: text | nullable
 - created_at: datetime
 - updated_at: datetime

## Download Links

### Table: wp_lchb_download_links
 - ID: int
 - release_id: int
 - link: string
 - type: string
 - user_id: int | nullable
 - expires_at: datetime | nullable
 - allowed_ips: string | nullable
 - status: string | nullable
 - created_at: datetime
 - updated_at: datetime

