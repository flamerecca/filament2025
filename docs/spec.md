# 商品系統規格文件

本文檔說明本專案中「商品（Product）／商品種類（Category）／促銷活動（Promotion）」系統的資料模型、關聯、欄位與基本行為，並作為之後擴充（如後台 CRUD、API、前端頁面）的基礎。

## 目標
- 管理商品與商品種類（含階層分類）。
- 管理促銷活動並將其套用到商品上。
- 提供查詢目前有效（active + 時間區間內）的促銷活動能力。

## 名詞定義
- 商品（Product）：可販售的單位，具備價格、庫存、SKU 等資訊。
- 商品種類（Category）：商品的分類，可選擇階層式結構（父子分類）。
- 促銷活動（Promotion）：針對商品提供折扣的行銷活動，支援百分比或固定金額兩種型別，並具備起迄時間與啟用狀態。

## 資料模型與關聯

### Category（categories）
- 欄位
  - id: BIGINT, PK
  - name: string
  - slug: string, unique
  - description: text, nullable
  - parent_id: FK -> categories.id, nullable（支援階層）
  - timestamps
- 關聯
  - parent(): belongsTo(Category)
  - children(): hasMany(Category)
  - products(): hasMany(Product)

### Product（products）
- 欄位
  - id: BIGINT, PK
  - category_id: FK -> categories.id（商品屬於一個分類）
  - name: string
  - sku: string, unique
  - description: text, nullable
  - price: decimal(10,2)
  - stock: unsignedInteger，預設 0
  - active: boolean，預設 true
  - timestamps
- 關聯
  - category(): belongsTo(Category)
  - promotions(): belongsToMany(Promotion)（透過 promotion_product）

### Promotion（promotions）
- 欄位
  - id: BIGINT, PK
  - name: string
  - type: string（`percentage` 或 `fixed_amount`）
  - discount_value: decimal(10,2)
  - starts_at: timestamp, nullable
  - ends_at: timestamp, nullable
  - active: boolean，預設 true
  - timestamps
- 關聯
  - products(): belongsToMany(Product)
- 常用查詢 Scope
  - scopeCurrentlyActive(): 僅回傳 active = true 且（在 starts_at 之後、ends_at 之前，或未設定時間）的活動。

### Pivot：promotion_product
- 欄位
  - id: BIGINT, PK
  - promotion_id: FK -> promotions.id
  - product_id: FK -> products.id
  - timestamps
  - unique(promotion_id, product_id)

## 業務規則（初版）
- 促銷活動是否「目前有效」：active = true，且
  - starts_at 為空或 <= 現在時間
  - 且 ends_at 為空或 >= 現在時間
- 折扣型別：
  - percentage：discount_value 表示折扣百分比（例如 10 代表 10%）。
  - fixed_amount：discount_value 表示固定減免金額（例如 100 表示折 100 元）。
- 一個商品可同時套用多個促銷活動；（定價策略待定）目前僅提供查詢有效促銷活動清單，實際售價計算規則可於未來新增（例如取最大折扣、按順序套用等）。

## 延伸規劃（未來可擴充）
- 後台管理：以 Filament v4 建立 Resource（商品、分類、促銷）之 CRUD 與操作。
- 前台與 API：
  - 提供產品列表、分類過濾、促銷標示。
  - 訂單／購物車整合，於結帳時計算最終價格。
- 權限與審核流程：限制誰能建立或啟用促銷。
- 報表與效益分析：促銷轉換率、商品銷售排行等。

## 測試策略（現況）
- 已撰寫基本 Feature 測試，驗證：
  - 可建立商品並關聯到分類。
  - 促銷可關聯到商品，且能正確查詢「目前有效」的促銷活動。

## 檔案一覽（與本規格相關）
- app/Models/Category.php
- app/Models/Product.php
- app/Models/Promotion.php
- database/migrations/*_create_categories_table.php
- database/migrations/*_create_products_table.php
- database/migrations/*_create_promotions_table.php
- database/migrations/*_create_promotion_product_table.php
- database/factories/CategoryFactory.php
- database/factories/ProductFactory.php
- database/factories/PromotionFactory.php
- tests/Feature/ProductPromotionTest.php
