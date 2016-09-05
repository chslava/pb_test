-- Задание 2, п.3.а  --

SELECT category_name FROM category WHERE id IN (
  SELECT category_id FROM category_product WHERE product_id IN (
    SELECT id FROM product WHERE product_name IN ('Шлепки', 'Ласты', 'Клеш', 'Джинсы')
  )
);

SELECT c.category_name FROM category AS c, category_product AS cp, product AS p
WHERE p.product_name IN ('Шлепки', 'Ласты', 'Клеш', 'Джинсы') AND cp.product_id=p.id AND c.id=cp.category_id GROUP BY c.id;

SELECT c.category_name FROM product AS p
INNER JOIN category_product AS cp ON p.id = cp.product_id
INNER JOIN category AS c ON c.id = cp.category_id
WHERE product_name IN ('Шлепки', 'Ласты', 'Клеш', 'Джинсы') GROUP BY c.id;

-- Задание 2, п.3.b  --

SELECT p.product_name
FROM category AS child,
    category AS parent,
    category_product AS cp,
    product AS p
WHERE child.lft BETWEEN parent.lft AND parent.rgt
      AND parent.category_name = 'Обувь'
      AND child.id=cp.category_id
      AND p.id=cp.product_id
GROUP BY p.product_name;

-- Задание 2, п.3.c  --

SELECT parent.category_name, SUM(p.qty)
FROM category AS child ,
     category AS parent,
     category_product AS cp,
     product AS p
WHERE child.lft BETWEEN parent.lft AND parent.rgt
      AND child.id = cp.category_id
      AND parent.category_name IN ('Обувь', 'Классика')
      AND p.id = cp.product_id
GROUP BY parent.category_name
ORDER BY parent.category_name;

-- Задание 2, п.3.d  --

SELECT parent.category_name, COUNT(cp.product_id)
FROM category AS child ,
  category AS parent,
  category_product AS cp
WHERE child.lft BETWEEN parent.lft AND parent.rgt
      AND child.id = cp.category_id AND parent.category_name IN ('Обувь', 'Классика')
GROUP BY parent.category_name
ORDER BY parent.category_name;

-- Задание 2, п.3.e  --

SELECT parent.category_name
FROM category AS child,
     category AS parent
WHERE child.lft BETWEEN parent.lft AND parent.rgt
      AND child.category_name = 'Классика'
ORDER BY parent.lft;
