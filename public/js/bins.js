(() => {
    'use strict'
  
    const getStoredTheme = () => localStorage.getItem('theme')
    const setStoredTheme = theme => localStorage.setItem('theme', theme)
  
    const getPreferredTheme = () => {
      const storedTheme = getStoredTheme()
      if (storedTheme) {
        return storedTheme
      }
  
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }
  
    const setTheme = theme => {
      if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark')
      } else {
        document.documentElement.setAttribute('data-bs-theme', theme)
      }
    }
  
    setTheme(getPreferredTheme())
  
    const showActiveTheme = (theme, focus = false) => {
      const themeSwitcher = document.querySelector('#bd-theme')
  
      if (!themeSwitcher) {
        return
      }
  
      const themeSwitcherText = document.querySelector('#bd-theme-text')
      const activeThemeIcon = document.querySelector('.theme-icon-active use')
      const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
      const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')
  
      document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active')
        element.setAttribute('aria-pressed', 'false')
      })
  
      btnToActive.classList.add('active')
      btnToActive.setAttribute('aria-pressed', 'true')
      activeThemeIcon.setAttribute('href', svgOfActiveBtn)
      const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
      themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)
  
      if (focus) {
        themeSwitcher.focus()
      }
    }
  
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      const storedTheme = getStoredTheme()
      if (storedTheme !== 'light' && storedTheme !== 'dark') {
        setTheme(getPreferredTheme())
      }
    })
  
    window.addEventListener('DOMContentLoaded', () => {
      showActiveTheme(getPreferredTheme())
  
      document.querySelectorAll('[data-bs-theme-value]')
        .forEach(toggle => {
          toggle.addEventListener('click', () => {
            const theme = toggle.getAttribute('data-bs-theme-value')
            setStoredTheme(theme)
            setTheme(theme)
            showActiveTheme(theme, true)
          })
        })
    })
  })()

document.addEventListener('gesturestart', function(e) {
    e.preventDefault();
    // special hack to prevent zoom-to-tabs gesture in safari
    document.body.style.zoom = 0.99;
});

document.addEventListener('gesturechange', function(e) {
    e.preventDefault();
    // special hack to prevent zoom-to-tabs gesture in safari
    document.body.style.zoom = 0.99;
});

document.addEventListener('gestureend', function(e) {
    e.preventDefault();
    // special hack to prevent zoom-to-tabs gesture in safari
    document.body.style.zoom = 0.99;
});

  //today or tomorrow or date format
function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100
    if (j == 1 && k != 11) {
        return i + "st"
    }
    if (j == 2 && k != 12) {
        return i + "nd"
    }
    if (j == 3 && k != 13) {
        return i + "rd"
    }
    return i + "th"
}

var todayString = new Date().toDateString()
var tomorrowString = new Date()
var yesterdayString = new Date()
tomorrowString.setDate(tomorrowString.getDate() + 1)
tomorrowString = tomorrowString.toDateString();
yesterdayString.setDate(yesterdayString.getDate() - 1)
yesterdayString = yesterdayString.toDateString()
                

var nextCollection = document.getElementById("next-collection")
var binDates = document.querySelectorAll(".bindate")

binDates.forEach(binDate => {
    var theDate = binDate.innerHTML
    var utcDate = new Date(theDate)
    var localDate = new Date(utcDate.getTime() + utcDate.getTimezoneOffset() * 60000) //local Date
    var localDateString = localDate.toDateString();
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let formatted_date = ordinal_suffix_of(localDate.getDate()) + " " + months[localDate.getMonth()]

    if(todayString === localDateString){
        binDate.textContent='today'
        //nextCollection.textContent = "Today's collection: "
    }else if(tomorrowString === localDateString){
        binDate.textContent='tomorrow'
        //nextCollection.textContent = "Tomorrow's collection: "
    }else if(yesterdayString === localDateString){
        binDate.textContent='yesterday'
        //nextCollection.textContent = "Yesterday's collection: "
    }else{
        binDate.textContent=formatted_date
    }
})

//bins logo
document.getElementById("bin-logo").innerHTML = document.getElementById("logo-replace").innerHTML
document.getElementById("logo-replace").style.display = 'none'



//calendar

let today = new Date()
let currentMonth = today.getMonth()
let currentYear = today.getFullYear()
let selectYear = document.getElementById("year") >= yearCeiling ? yearCeiling : document.getElementById("year") < yearFloor ? yearFloor : document.getElementById("year")
let selectMonth = document.getElementById("month")
let months = ["January", "February", "March", "April", "May", "June", "July", "August", "Sep", "October", "November", "December"]
let monthAndYear = document.getElementById("monthAndYear")
var yearCeiling = 2026
var yearFloor = 2023
var monthFloor = 11

monthFloor--

function next() {
    let newCurrentYear = (currentMonth === 11) ? currentYear + 1 : currentYear
    let newCurrentMonth = (currentMonth + 1) % 12
    if (newCurrentYear < yearCeiling) {
        currentYear = newCurrentYear
        currentMonth = newCurrentMonth
        showCalendar(currentMonth, currentYear)
    }
}

function previous() {
    let newCurrentYear = (currentMonth === 0) ? currentYear - 1 : currentYear
    let newCurrentMonth = (currentMonth === 0) ? 11 : currentMonth - 1
    if (newCurrentYear >= yearFloor) {
        currentYear = newCurrentYear
        currentMonth = newCurrentMonth
        showCalendar(currentMonth, currentYear)
    }
}

function jump() {
    currentYear = parseInt(selectYear.value)
    currentMonth = parseInt(selectMonth.value)
    if (currentYear <= yearFloor && currentMonth <= monthFloor) {
        currentYear = yearFloor
        currentMonth = monthFloor 
    }
    showCalendar(currentMonth, currentYear)
}

function jumpToday() {
    currentMonth = today.getMonth()
    currentYear = today.getFullYear()
    showCalendar(currentMonth, currentYear)
}

function showCalendar(month, year) {
    document.getElementById("jumpToday").classList.remove("active")
    if (year == yearCeiling - 1 && month == 11) {
        document.getElementById("next").disabled = true
    } else if (document.getElementById("next").disabled == true) {
        document.getElementById("next").disabled = false
    }

    if (year == yearFloor && month == monthFloor) {
        document.getElementById("previous").disabled = true
    } else if (document.getElementById("previous").disabled == true) {
        document.getElementById("previous").disabled = false
    }

    let firstDay = (new Date(year, month)).getDay() - 1 == -1 ? 6 : (new Date(year, month)).getDay() - 1
    let daysInMonth = 32 - new Date(year, month, 32).getDate()
    let tbl = document.getElementById("calendar-body")

    // clearing all previous cells
    tbl.innerHTML = ""

    // filing data about month and in the page via DOM.
    monthAndYear.innerHTML = months[month] + " " + year
    selectYear.value = year
    selectMonth.value = month

    // creating all cells
    let date = 1
    for (let i = 0; i < 6; i++) {
        // creates a table row
        let row = document.createElement("tr")
        row.classList.add("tr-row")
        let cellEvent = document.createElement("div")

        //creating individual cells, filing them up with data.
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < firstDay) {
                let cell = document.createElement("td")
                let cellText = document.createTextNode("")
                cell.appendChild(cellText)
                cell.appendChild(cellEvent)
                row.appendChild(cell)
            }
            else if (date > daysInMonth) {
                break
            }

            else {
                let cell = document.createElement("td")
                let cellText = document.createTextNode(date)
                cell.appendChild(cellText)
                
                if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add("today")
                    document.getElementById("jumpToday").classList.add("active")
                } 

                if (date == '31' && month == '9') {
                    cell.classList.add("halloween")
                } 

                if (date == '25' && month == '11') {
                    cell.classList.add("christmas")
                } 

                var monthWith0 = month + 1
                var dateWith0 = date

                if (monthWith0 < 10) {
                    monthWith0 = `0${monthWith0}`
                }

                if (dateWith0 < 10) {
                    dateWith0 = `0${dateWith0}`
                }

                var dateToCheck = year+'-'+monthWith0+'-'+dateWith0

                if (all_bin_dates.brown.includes(dateToCheck)) {
                    cellEvent.innerHTML += `<svg style="color: #966757;" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 448 512"><path d="M284.2 0C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM207 199L127 279C117.7 288.4 117.7 303.6 127 312.1C136.4 322.3 151.6 322.3 160.1 312.1L199.1 273.9V408C199.1 421.3 210.7 432 223.1 432C237.3 432 248 421.3 248 408V273.9L287 312.1C296.4 322.3 311.6 322.3 320.1 312.1C330.3 303.6 330.3 288.4 320.1 279L240.1 199C236.5 194.5 230.4 191.1 223.1 191.1C217.6 191.1 211.5 194.5 207 199V199z" fill="#966757"></path></svg>`
                    cell.appendChild(cellEvent)
                }

                if (all_bin_dates.blue.includes(dateToCheck)) {
                    cellEvent.innerHTML += `<svg style="color: #007dbb;" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 448 512"><path d="M284.2 0C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM207 199L127 279C117.7 288.4 117.7 303.6 127 312.1C136.4 322.3 151.6 322.3 160.1 312.1L199.1 273.9V408C199.1 421.3 210.7 432 223.1 432C237.3 432 248 421.3 248 408V273.9L287 312.1C296.4 322.3 311.6 322.3 320.1 312.1C330.3 303.6 330.3 288.4 320.1 279L240.1 199C236.5 194.5 230.4 191.1 223.1 191.1C217.6 191.1 211.5 194.5 207 199V199z" fill="#007dbb"></path></svg>`
                    cell.appendChild(cellEvent)
                }

                if (all_bin_dates.green.includes(dateToCheck)) {
                    cellEvent.innerHTML += `<svg style="color: #038831;" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 448 512"><path d="M284.2 0C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM207 199L127 279C117.7 288.4 117.7 303.6 127 312.1C136.4 322.3 151.6 322.3 160.1 312.1L199.1 273.9V408C199.1 421.3 210.7 432 223.1 432C237.3 432 248 421.3 248 408V273.9L287 312.1C296.4 322.3 311.6 322.3 320.1 312.1C330.3 303.6 330.3 288.4 320.1 279L240.1 199C236.5 194.5 230.4 191.1 223.1 191.1C217.6 191.1 211.5 194.5 207 199V199z" fill="#038831"></path></svg>`
                    cell.appendChild(cellEvent)
                }
                
                if (all_bin_dates.grey.includes(dateToCheck)) {
                    cellEvent.innerHTML += `<svg style="color: #737373;" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 448 512"><path d="M284.2 0C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM207 199L127 279C117.7 288.4 117.7 303.6 127 312.1C136.4 322.3 151.6 322.3 160.1 312.1L199.1 273.9V408C199.1 421.3 210.7 432 223.1 432C237.3 432 248 421.3 248 408V273.9L287 312.1C296.4 322.3 311.6 322.3 320.1 312.1C330.3 303.6 330.3 288.4 320.1 279L240.1 199C236.5 194.5 230.4 191.1 223.1 191.1C217.6 191.1 211.5 194.5 207 199V199z" fill="#737373"></path></svg>`
                    cell.appendChild(cellEvent)
                }

                
                
                row.appendChild(cell)
                date++
            }


        }

        tbl.appendChild(row) // appending each row into calendar body.
    }

}

const tabEl = document.querySelector('#calendar-page-tab')
tabEl.addEventListener('shown.bs.tab', event => {
        document.getElementById("calendar-card").style.display = "block"
        showCalendar(currentMonth, currentYear)

})

const homeTab = document.querySelector('#this-week-tab')
homeTab.addEventListener('shown.bs.tab', event => {
        document.getElementById("calendar-card").style.display = "none"
})

