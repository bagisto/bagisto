-- ============================================================================
-- PostgreSQL initial setup for the Bagisto internal database.
--
-- Executed once during the image build, against a freshly created cluster.
-- PostgreSQL has no "CREATE ROLE IF NOT EXISTS", so the role is guarded by a
-- DO block; the database is guarded by \gexec over a SELECT that yields no
-- row when it already exists.
-- ============================================================================

DO
$$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'bagisto') THEN
        CREATE ROLE bagisto WITH LOGIN PASSWORD 'bagisto';
    END IF;
END
$$;

SELECT 'CREATE DATABASE bagisto OWNER bagisto ENCODING ''UTF8'''
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'bagisto')\gexec

GRANT ALL PRIVILEGES ON DATABASE bagisto TO bagisto;

-- Laravel creates tables in the "public" schema, which from PostgreSQL 15
-- onwards is not writable by ordinary roles without this grant.
\connect bagisto

GRANT ALL ON SCHEMA public TO bagisto;

ALTER SCHEMA public OWNER TO bagisto;
