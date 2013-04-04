persistence.store.websql.config(persistence, 'camilaframework9', 'Camila Framework Database', 5 * 1024 * 1024);

var Worktable = persistence.define('Worktable', {
	sequence: "INT",
	name: "TEXT",
	category: "TEXT",
	schema: "TEXT",
	columns: "JSON"
});

var Worktables = [];