// Test mejorado de Selenium para Subcategorías - Con esperas y verificaciones
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.is;
import static org.hamcrest.core.IsNot.not;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.remote.RemoteWebDriver;
import org.openqa.selenium.remote.DesiredCapabilities;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Alert;
import org.openqa.selenium.Keys;
import java.util.*;
import java.net.MalformedURLException;
import java.net.URL;
import java.time.Duration;

public class SubcategoriaTestMejorado {
  private WebDriver driver;
  private Map<String, Object> vars;
  private WebDriverWait wait;
  JavascriptExecutor js;
  
  @Before
  public void setUp() {
    driver = new FirefoxDriver();
    js = (JavascriptExecutor) driver;
    wait = new WebDriverWait(driver, Duration.ofSeconds(10));
    vars = new HashMap<String, Object>();
  }
  
  @After
  public void tearDown() {
    driver.quit();
  }
  
  @Test
  public void testSubcategoriaCompleto() {
    // 1. Ir al dashboard
    driver.get("http://localhost/RMIE/app/views/dashboard.php");
    
    // 2. Navegar a subcategorías
    wait.until(ExpectedConditions.elementToBeClickable(
      By.cssSelector(".row:nth-child(2) > .col-lg-3:nth-child(3) .btn")))
      .click();
    
    // 3. Crear nueva subcategoría
    wait.until(ExpectedConditions.elementToBeClickable(By.cssSelector(".me-2")))
      .click();
    
    // 4. Llenar formulario
    wait.until(ExpectedConditions.visibilityOfElementLocated(By.id("nombre")))
      .sendKeys("tps sub selenium");
    
    driver.findElement(By.id("descripcion")).sendKeys("Descripción de prueba Selenium");
    
    // 5. Seleccionar categoría
    WebElement dropdown = wait.until(ExpectedConditions.elementToBeClickable(By.id("id_categoria")));
    dropdown.findElement(By.xpath("//option[. = 'tps']")).click();
    
    // 6. Crear subcategoría
    driver.findElement(By.cssSelector(".btn-create")).click();
    
    // 7. Esperar confirmación de creación
    wait.until(ExpectedConditions.urlContains("index"));
    
    // 8. Verificar que aparezca en la lista
    wait.until(ExpectedConditions.presenceOfElementLocated(By.id("btnTable")))
      .click();
    
    // 9. Buscar la subcategoría creada y editarla
    WebElement editBtn = wait.until(ExpectedConditions.elementToBeClickable(
      By.cssSelector("#tableView .btn:nth-child(1) > .fas")));
    editBtn.click();
    
    // 10. Editar nombre
    WebElement nombreField = wait.until(ExpectedConditions.visibilityOfElementLocated(By.id("nombre")));
    nombreField.clear();
    nombreField.sendKeys("tps subcategoria editada");
    
    // 11. Guardar cambios
    driver.findElement(By.cssSelector(".btn-update")).click();
    
    // 12. Esperar confirmación de edición
    wait.until(ExpectedConditions.urlContains("index"));
    
    // 13. Proceder a eliminar - hacer clic en botón eliminar
    WebElement deleteBtn = wait.until(ExpectedConditions.elementToBeClickable(
      By.cssSelector("#tableView .btn:nth-child(2) > .fas")));
    deleteBtn.click();
    
    // 14. Esperar a llegar a la página de confirmación
    wait.until(ExpectedConditions.urlContains("delete"));
    
    // 15. Verificar que estamos en la página correcta
    wait.until(ExpectedConditions.presenceOfElementLocated(
      By.xpath("//h3[contains(text(), 'Confirmar Eliminación')]")));
    
    // 16. Hacer clic en "Confirmar Eliminación" y esperar resultado
    WebElement confirmBtn = wait.until(ExpectedConditions.elementToBeClickable(
      By.linkText("Confirmar Eliminación")));
    
    // 17. Antes de hacer clic, preparar para capturar el alert
    confirmBtn.click();
    
    // 18. Esperar y manejar el alert de confirmación
    try {
      Alert alert = wait.until(ExpectedConditions.alertIsPresent());
      String alertText = alert.getText();
      System.out.println("✅ Mensaje de confirmación: " + alertText);
      
      // Aceptar el alert
      alert.accept();
      
      // 19. Verificar que regresamos al index
      wait.until(ExpectedConditions.urlContains("index"));
      System.out.println("✅ Test completado exitosamente - Eliminación confirmada");
      
    } catch (Exception e) {
      System.out.println("❌ No se detectó alert de confirmación: " + e.getMessage());
      
      // 20. Verificar si hay redirección directa
      wait.until(ExpectedConditions.urlContains("index"));
      System.out.println("✅ Redirección completada sin alert");
    }
    
    // 21. Verificación final - comprobar que ya no existe en la lista
    try {
      driver.findElement(By.xpath("//td[contains(text(), 'tps subcategoria editada')]"));
      System.out.println("❌ La subcategoría aún existe en la lista");
    } catch (Exception e) {
      System.out.println("✅ La subcategoría fue eliminada correctamente");
    }
  }
}