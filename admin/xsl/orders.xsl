<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>
<xsl:template match="/">
<table class="formTbl" align="center">
	<colgroup>
	<col width="120"/>
	<col/>
	</colgroup>
	<xsl:apply-templates select="/data/row"/>
</table>
</xsl:template>

<xsl:template match="row">
	<tr>
		<th>配置单类型：</th>
		<td><xsl:value-of select="@cName"/></td>
	</tr>
	<tr>
		<th>配置单名称：</th>
		<td><xsl:value-of select="@title"/></td>
	</tr>
	<tr>
		<th valign="top">配置单说明：</th>
		<td><xsl:value-of select="@content" disable-output-escaping="yes"/></td>
	</tr>
	<tr>
		<th>总价格：</th>
		<td><xsl:value-of select="@totalPrice"/></td>
	</tr>
	<tr>
		<th valign="top">配置单：</th>
		<td>
			<table class="pzdTbl" align="center">
				<colgroup>
				<col width="80"/>
				<col width="220"/>
				<col width="60"/>
				<col width="60"/>
				</colgroup>
				<tr>
					<th>配置</th>
					<th>品牌型号</th>
					<th>数量</th>
					<th>单价</th>
				</tr>
				<xsl:apply-templates select="ordersDesc/item"/>
			</table>
		</td>
	</tr>
</xsl:template>

<xsl:template match="ordersDesc/item">
	<tr>
		<td><xsl:value-of select="@配置"/></td>
		<td><xsl:value-of select="@品牌型号"/></td>
		<td><xsl:value-of select="@数量"/></td>
		<td><xsl:value-of select="@单价"/></td>
	</tr>
</xsl:template>
</xsl:stylesheet>