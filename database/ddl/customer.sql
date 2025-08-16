-- Clientes
CREATE TABLE customers (
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(255)  NOT NULL,
    email      CITEXT        NOT NULL UNIQUE,
    password   VARCHAR(255),
    created_at TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMPTZ   NULL,
    UNIQUE (email)
);

CREATE INDEX idx_customers_email ON customers (email);
