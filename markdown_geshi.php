<?php
/**
 * MarkdownExtra + GeSHi でコードブロックでシンタックスハイライトする
 * 
 * Example:
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~ .html
 * これは <span style="color:red;font-size:150%;">HTML インライン要素</span>です。
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @author S.Koga
 * @since 2016-09-08
 * @version 1.0
 **/

/**
 * Define DocBlock
 **/

// Load umodified Michel Fortin's PHP Markdown Extra: http://michelf.com/projects/php-markdown/
define('MARKDOWN_PARSER_CLASS',  'MarkdownExtraGeshi_Parser');
require_once (DOKU_PLUGIN . 'markdownextra/markdown.php');

class MarkdownExtraGeshi_Parser extends MarkdownExtra_Parser
{
    function _doFencedCodeBlocks_callback($matches) {
        $classname =& $matches[2];
        $attrs     =& $matches[3];
        $codeblock = $matches[4];
        $codeblock = preg_replace_callback('/^\n+/',
            array(&$this, '_doFencedCodeBlocks_newlines'), $codeblock);

        if (empty($classname) && !empty($attrs)) {
            list($classname, $filename) = explode(':', $attrs, 2);
            $classname = preg_replace('/^\./', '', $classname);
        }

        if ($classname === 'html') {
            $classname = 'html4strict';
        }

        $codeblock = p_xhtml_cached_geshi($codeblock, $classname ?: 'txt');

        return "\n\n".$this->hashBlock($codeblock)."\n\n";
    }
}

?>