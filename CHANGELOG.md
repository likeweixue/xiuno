# CHANGELOG

## v4.0.5 (2026-11-07)

### 环境适配：支持 PHP 8 与 MySQL 8

本次迭代将 Xiuno BBS 从旧版 PHP 5/7 + MySQL 5.x 环境升级适配到 **PHP 8.0+** 与 **MySQL 8.0+**，实现"与时俱进"。

#### 数据库驱动层

- 重写 `xiunophp/db_mysql.class.php`：由已移除的 `mysql_*` 系列函数（PHP 7.0 起废弃）迁移至 PDO（`pdo_mysql` 扩展），保持类名 `db_mysql` 与对外接口不变，配置 `type` 仍兼容 `mysql` 与 `pdo_mysql`。
- 修复 `xiunophp/db_pdo_mysql.class.php` 中多处兼容性问题：
  - `strtoupper(substr($sql, 0, 12) == 'CREATE TABLE')` 比较运算符优先级错误 → `strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE'`。
  - `catch (Exception $e)` → `catch (PDOException $e)`，确保 PDO 异常被正确捕获。
  - `lastinsertid()` → `lastInsertId()`（PHP 方法名大小写）。
  - 启用 `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`，保证异常模式下错误可捕获。
  - 连接 DSN 补充 `charset` 参数，避免乱码。

#### 移除 PHP 8 已废弃特性

- `get_magic_quotes_gpc()` / `set_magic_quotes_runtime()`（PHP 7.4 移除）：在 `xiunophp/xiunophp.php` 中改为恒为 `FALSE` 的常量标志位；清理 `xn_send_mail.func.php` 中 PHPMailer 旧代码里的 magic_quotes 处理逻辑。
- **花括号数组/字符串偏移访问语法**（`$str{0}` / `$arr{$key}`，PHP 7.4 废弃、PHP 8 移除）：修复 `xiunophp/xn_html_safe.func.php` 中 HTML 安全过滤解析器的 3 处 `$this->rawtext{...}` 写法，统一改为方括号 `$this->rawtext[...]`。

#### 安装流程

- `install/index.php`：移除 `mysql_query("CREATE DATABASE ...")`，统一使用 PDO 建库；修复 `strpos(':', $host)` 参数顺序错误 → `strpos($host, ':')`；建库默认字符集设为 `utf8mb4`。
- `install/index.php`：环境检测统一基于 `pdo_mysql` 扩展（`mysql_*` 函数已不存在）。
- `install/install.func.php`：PHP 版本要求由 `5.0` 提升至 `8.0`。
- `install/view/htm/db.htm`：默认数据库引擎由 MyISAM 改为 InnoDB。

#### SQL 表结构

- `install/install.sql`：全部 17 张表由 `ENGINE=MyISAM` 迁移至 `ENGINE=InnoDB`；字符集由 `utf8`/`utf8_general_ci` 升级为 `utf8mb4`/`utf8mb4_general_ci`，完整支持 4 字节 emoji 表情。

#### 入口加载

- `index.php`：统一加载已适配 PHP 8 的 `xiunophp.php`，不再加载内嵌旧版代码的合并文件 `xiunophp.min.php`（如需重新生成可运行 `php tool/merge.php`）。

---

## v4.0.4 (初始版本)

- 归档 Xiuno BBS 4.0.4（修罗论坛最后一个版本号）完整源码，作为后续迭代的基线。
