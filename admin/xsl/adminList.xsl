<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>

<xsl:template match="/">
<table class="tblList">
	<colgroup>
	<col />
	<col width="200" />
	<col width="200" />
	</colgroup>
  <tr>
    <th>账号</th>
    <th>密码</th>
    <th>操作</th>
  </tr>
<xsl:apply-templates />
</table>
</xsl:template>

<xsl:template match="row">
  <tr>
  <td><a href="?act=edit&amp;id={@id}" popbox="1" boxTi="编辑管理员"><xsl:value-of select="@userName"/></a></td>
  <td><xsl:value-of select="@pwd"/></td>
  <td><a href="javascript:void(0);"  onclick="chkLink('?act=saveDel&amp;id={@id}',this,'确定要删除吗?')">删除</a></td>
  </tr>
</xsl:template>

</xsl:stylesheet>