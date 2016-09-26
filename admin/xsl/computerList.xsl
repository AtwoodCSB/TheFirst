<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>
<xsl:template match="/">
<table class="tblList">
	<colgroup>
	<col />
	<col width="80" />
	<col width="80" />
	<col width="80" />
	</colgroup>
  <tr>
    <th>配件名称</th>
    <th>分类</th>
    <th>价格</th>
    <th>操作</th>
  </tr>
<xsl:apply-templates />
<tr>
	<td colspan="4">
		{%spbar%}
	</td>
</tr>
</table>
</xsl:template>

<xsl:template match="row">
  <tr>
  <td><xsl:value-of select="@title"/></td>
	<td><xsl:value-of select="@cName"/></td>
	<td><xsl:value-of select="@price"/></td>
  <td>
		<a href="?act=edit&amp;id={@id}">编辑</a>&#160;
		<a href="javascript:void(0);"  onclick="chkLink('?act=saveDel&amp;id={@id}',this,'确定要删除吗?')">删除</a>
	</td>
  </tr>
</xsl:template>

</xsl:stylesheet>