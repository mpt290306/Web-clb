create table if not exists public.site_data (
    name text primary key,
    payload jsonb not null,
    updated_at timestamptz not null default now()
);

alter table public.site_data enable row level security;
revoke all on public.site_data from anon, authenticated;
grant select, insert, update, delete on public.site_data to service_role;

-- No public policies: only the server-side secret key can read or change these rows.
-- Create a public Storage bucket named site-media in the Supabase dashboard.
-- Limit it to JPG, PNG, WebP and GIF, with a 5 MB file-size limit.
