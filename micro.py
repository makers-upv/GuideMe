from microbit import *

def readAngle(letter):

    if letter == 'NN':
        angle = 0
    if letter == 'NE':
        angle = 45
    if letter == 'EE':
        angle = 90
    if letter == 'SE':
        angle = 135
    if letter == 'SS':
        angle = 180
    if letter == 'SW':
        angle = 225
    if letter == 'WW':
        angle = 270
    if letter == 'NW':
        angle = 315
    return angle

def getCoordinate(bearing): #cambiar 180 grados todo levogiro
    #sleep(100)

    if (bearing>=337.5 and bearing<360) or (bearing>=0 and bearing<22.5):
        direction='SS'
    elif bearing>=22.5 and bearing<67.5:
        direction='SW'
    elif bearing>=67.5 and bearing<112.5:
        direction='WW'
    elif bearing>=112.5 and bearing<157.5:
        direction='NW'
    elif bearing>=157.5 and bearing<202.5:
        direction='NN'
    elif bearing>=202.5 and bearing<247.5:
        direction='NE'
    elif bearing>=247.5 and bearing<292.5:
        direction='EE'
    elif bearing>=292.5 and bearing<337.5:
        direction='SE'
    return direction

def setDisplay(coord):
    #sleep(100)

    if coord == 'NN':
        display.show(Image.ARROW_N)
    elif coord == 'NE':
        display.show(Image.ARROW_NE)
    elif coord == 'EE':
        display.show(Image.ARROW_E)
    elif coord == 'SE':
        display.show(Image.ARROW_SE)
    elif coord == 'SS':
        display.show(Image.ARROW_S)
    elif coord == 'SW':
        display.show(Image.ARROW_SW)
    elif coord == 'WW':
        display.show(Image.ARROW_W)
    elif coord == 'NW':
        display.show(Image.ARROW_NW)

### --- MAIN ---- ###

coords = ["NN", "NE", "EE", "SE", "SS", "SW", "WW", "NW"]

uart.init(115200, rx=pin0, tx=pin2)

def run():

    while True:
        sleep(100)
        if uart.any() == True:

            pin1.write_digital(0)
            dir_goal = uart.readline()

            for coord in coords:
                if coord in dir_goal:
                    dir_goal = coord
            display.scroll(dir_goal) #quitar en el producto final

            while getCoordinate(compass.heading()) is not dir_goal: #simplemente despues de sleep setDisplay(dir_goal)

                sleep(500)
                setDisplay(getCoordinate((360+(readAngle(dir_goal)-compass.heading()))%360))

            if getCoordinate(compass.heading()) == dir_goal:
                pin1.write_digital(1)
                sleep(500)
                pin1.write_digital(0)
                reset()
run()
