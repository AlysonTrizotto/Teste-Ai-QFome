-- Favoritos do cliente
CREATE TABLE customer_favorites (
    id          BIGSERIAL PRIMARY KEY,
    customer_id BIGINT NOT NULL REFERENCES customers(id) ON DELETE CASCADE,
    product_id  BIGINT NOT NULL, -- ID externo do produto (FakeStore)
    created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    deleted_at  TIMESTAMPTZ NULL,
    UNIQUE (customer_id, product_id)
);

CREATE INDEX idx_customer_favorites_customer ON customer_favorites (customer_id);
CREATE INDEX idx_customer_favorites_product  ON customer_favorites (product_id);