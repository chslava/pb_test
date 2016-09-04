-- Задание 2, п.3.а  --

SELECT category_name FROM category WHERE id IN (
  SELECT category_id FROM category_product WHERE product_id IN (
    SELECT id FROM product WHERE product_name IN ('Шлепки', 'Ласты', 'Клеш', 'Джинсы')
  )
);

SELECT id FROM product
  LEFT JOIN category_product ON product.id = category_product.product_id
  LEFT JOIN category ON category.id = category_product.category_id
  WHERE product_name IN ('Шлепки', 'Ласты', 'Клеш', 'Джинсы')
;

-- Задание 2, п.3.b  --

SELECT product_name FROM product WHERE id IN (
  SELECT product_id FROM category_product WHERE category_id IN (
    SELECT id FROM category WHERE category_path LIKE ('%/4') OR category.category_path LIKE ('%/4/%')
  )
);

-- Задание 2, п.3.c  --

SELECT category_name, sum(qty) FROM category
  LEFT JOIN category_product ON category.id=category_product.category_id
  LEFT JOIN product ON product.id=category_product.product_id
WHERE category_id IN (9,10,1)
GROUP BY category_id
;

-- Задание 2, п.3.d  --

SELECT category_name, COUNT(product_id) FROM category
  LEFT JOIN category_product ON category.id=category_product.category_id
WHERE category_id IN (9,10,1)
GROUP BY category_id
;

-- Задание 2, п.3.e  --

DROP PROCEDURE IF EXISTS get_category_breadcrumb;
CREATE PROCEDURE get_category_breadcrumb(path TEXT)
  BEGIN
    declare i int default 0;
    DECLARE breadcrumb TEXT;
    DECLARE name TEXT;
    DECLARE cat_id TEXT;

    WHILE LENGTH(path) > 0 DO
      SET i = LOCATE('/', path);
      IF (i = 0)
      THEN SET i = LENGTH(path) + 1;
      END IF;
      SET cat_id = SUBSTRING(path, 1, i - 1);
      SELECT category_name INTO name FROM category WHERE id=cat_id;
      SET breadcrumb = concat_ws('/', breadcrumb, name);
      SET path = SUBSTRING(path, i + 1, LENGTH(path));
    END WHILE;
    SELECT breadcrumb;
  END;



CALL get_category_breadcrumb(
    (SELECT category_path FROM category WHERE id=1)
);